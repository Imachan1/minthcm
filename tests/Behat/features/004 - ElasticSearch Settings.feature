@MintHCM
Feature: Testy konfiguracji ElasticSearch

  Background:
    Given I am logged in as "admin" "t4jn3h4slo"

  @javascript @suite
  Scenario:
    When I go to page "GlobalSearchSettingsPage"
