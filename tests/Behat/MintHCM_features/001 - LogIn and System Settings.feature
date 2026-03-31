@MintHCM
Feature: Testy ustawień systemu

  Background:
    Given I am logged in as "admin" "t4jn3h4slo"

  @javascript @MintHCM
  Scenario:
    When I go to page "SystemSettingsPage"
    Then Field "Show Full Names" should contain "1"
    And Field "Developer Mode" should contain "0"
    And Field "Validate user IP address" should contain "0"
    And Field "Log Level" should contain "error"