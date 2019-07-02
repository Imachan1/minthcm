@MintHCM
Feature: Testy ustawień regionalnych (Locale)

  Background:
    Given I am logged in as "admin" "t4jn3h4slo"

  @javascript @MintHCM
  Scenario:
    When I go to page "LocaleSettingsPage"
    Then Field "Date Format" should contain "d/m/Y"
    And Field "Time Format" should contain "H:i"
    And Field "Language" should contain "pl_PL"
    And Field "Currency" should contain "Złoty"
    And Field "Currency Symbol" should contain "zł"
    And Field "Currency on right" should contain "1"
    And Field "ISO 4217 Currency Code" should contain "PLN"
    And Field "1000s Separator" should contain " "
    And Field "Decimal Symbol" should contain ","
