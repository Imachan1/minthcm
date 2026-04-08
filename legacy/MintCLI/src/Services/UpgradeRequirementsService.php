<?php

namespace MintHCM\MintCLI\Services;

/**
 * Reads upgrade requirements from a target git tag without checking it out,
 * then verifies the current environment meets those requirements.
 */
class UpgradeRequirementsService
{
    private array $errors   = [];
    private array $warnings = [];

    /**
     * Load requirements.json from the target tag using `git show` (no checkout needed).
     * Reads upgrade/{$tag}/requirements.json from the tag.
     */
    public function loadRequirementsFromTag(string $tag): ?array
    {
        return $this->loadRequirements($tag, $tag);
    }

    /**
     * Load requirements.json for a given upgrade version, read from the target tag.
     * Useful when the effective upgrade version differs from the checkout tag
     * (e.g. upgrading to 4.3.1 but running 4.3.0 scripts).
     */
    public function loadRequirements(string $tag, string $upgrade_version): ?array
    {
        $path = "upgrade/{$upgrade_version}/requirements.json";
        $json = shell_exec("git show " . escapeshellarg("{$tag}:{$path}") . " 2>/dev/null");

        if (empty($json)) {
            return null;
        }

        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Run all environment checks against the given requirements.
     * Returns true if all pass, false otherwise. Collect errors via getErrors().
     */
    public function verify(array $requirements, string $current_version): bool
    {
        $this->errors   = [];
        $this->warnings = [];

        if (!empty($requirements['from_versions'])) {
            $this->checkFromVersion($current_version, $requirements['from_versions']);
        }

        if (!empty($requirements['php'])) {
            $this->checkPhpVersion($requirements['php']);
        }

        if (!empty($requirements['mysql'])) {
            $this->checkMysqlVersion($requirements['mysql']);
        }

        if (!empty($requirements['elasticsearch'])) {
            $this->checkElasticsearchVersion($requirements['elasticsearch']);
        }

        if (!empty($requirements['disk_space_mb'])) {
            $this->checkDiskSpace((int) $requirements['disk_space_mb']);
        }

        if (!empty($requirements['node'])) {
            $this->checkNodeVersion($requirements['node']);
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function getSkipSteps(array $requirements): array
    {
        return $requirements['skip_steps'] ?? [];
    }

    public function getNotes(array $requirements): ?string
    {
        return $requirements['notes'] ?? null;
    }

    // -------------------------------------------------------------------------

    private function checkFromVersion(string $current_version, array $allowed_versions): void
    {
        foreach ($allowed_versions as $pattern) {
            if ($this->versionMatchesPattern($current_version, $pattern)) {
                return;
            }
        }

        $allowed = implode(', ', $allowed_versions);
        $this->errors[] = "Direct upgrade from version {$current_version} is not supported. Supported source versions: {$allowed}.";
    }

    /**
     * Match a version string against a pattern that may contain a wildcard (*).
     * Examples: "4.2.*" matches "4.2.0", "4.2.1", "4.2.11". "4.2.0" matches only "4.2.0".
     */
    private function versionMatchesPattern(string $version, string $pattern): bool
    {
        if (strpos($pattern, '*') === false) {
            return $version === $pattern;
        }

        $regex = '/^' . str_replace('\*', '\d+', preg_quote($pattern, '/')) . '$/';
        return (bool) preg_match($regex, $version);
    }

    private function checkPhpVersion(array $php_req): void
    {
        $current = PHP_VERSION;
        $min = $php_req['min'] ?? null;
        $max = $php_req['max'] ?? null;

        if ($min && version_compare($current, $min, '<')) {
            $this->errors[] = "PHP {$current} is below required minimum {$min}.";
        }

        if ($max && version_compare($current, $max, '>')) {
            $this->errors[] = "PHP {$current} exceeds maximum supported version {$max}.";
        }
    }

    private function checkMysqlVersion(array $mysql_req): void
    {
        $min = $mysql_req['min'] ?? null;
        if (!$min) {
            return;
        }

        $raw = shell_exec("mysql --version 2>/dev/null");
        if (empty($raw)) {
            $this->errors[] = "Could not determine MySQL/Percona version (mysql command not found).";
            return;
        }

        if (preg_match('/(\d+\.\d+\.\d+)/', $raw, $matches)) {
            $current = $matches[1];
            if (version_compare($current, $min, '<')) {
                $this->errors[] = "MySQL/Percona {$current} is below required minimum {$min}.";
            }
        }
    }

    private function checkElasticsearchVersion(array $es_req): void
    {
        $min = $es_req['min'] ?? null;
        $max = $es_req['max'] ?? null;

        $raw = shell_exec("curl -s http://localhost:9200 2>/dev/null");
        if (empty($raw)) {
            // Not a hard failure — ES might be on a different host; warn only
            return;
        }

        $data = json_decode($raw, true);
        $current = $data['version']['number'] ?? null;
        if (!$current) {
            return;
        }

        if ($min && version_compare($current, $min, '<')) {
            $this->errors[] = "Elasticsearch {$current} is below required minimum {$min}.";
        }

        if ($max && version_compare($current, $max, '>')) {
            $this->errors[] = "Elasticsearch {$current} exceeds maximum supported version {$max}.";
        }
    }

    private function checkDiskSpace(int $required_mb): void
    {
        $free_bytes = disk_free_space('.');
        if ($free_bytes === false) {
            return;
        }

        $free_mb = (int) ($free_bytes / 1024 / 1024);
        if ($free_mb < $required_mb) {
            $this->errors[] = "Insufficient disk space: {$free_mb} MB available, {$required_mb} MB required.";
        }
    }

    private function checkNodeVersion(array $node_req): void
    {
        $min = $node_req['min'] ?? null;
        if (!$min) {
            return;
        }

        $raw = shell_exec("node --version 2>/dev/null");
        if (empty($raw)) {
            $this->warnings[] = "Node.js not found. Version {$min}+ is recommended for frontend build.";
            return;
        }

        // node --version returns "v21.0.0" — strip the leading "v"
        $current = ltrim(trim($raw), 'v');
        if (version_compare($current, $min, '<')) {
            $this->warnings[] = "Node.js {$current} is below recommended version {$min}. Frontend build may fail.";
        }
    }
}
