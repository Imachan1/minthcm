@MintHCM
Feature: Testy ustawień skrzynki pocztowej

  Background:
    Given I am logged in as "admin" "t4jn3h4slo"

  @javascript @suite
  Scenario:
    When I go to page "SystemEmailSettingsPage"
    Then Field "Assignment Notifications" should contain "1"

    When I click "Send Test Email"
    Then Field "Email Address For Test Notification" should contain "szymon.rydza@evolpe.pl"
    
    When I click "Send"
    And I wait "10" seconds
    Then I should see "An email was sent to the specified email address using the provided outgoing mail settings."
    
    
