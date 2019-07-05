@MintHCM
Feature: Testy wyszukiwania ElasticSearch

  Background:
    Given I am logged in as "admin" "t4jn3h4slo"

@javascript @MintHCM
  Scenario:
    When I go to page "GlobalSearchResultsPage"
    And I search for all Global Search results
    Then I can not see 500 page
    And I should see Global Search results
    
  @javascript @MintHCM
  Scenario:
    When I go to page "GlobalSearchResultsPage"
    And I search for "123456789phraseThatDoesNotExist" in Global Search
    Then I can not see 500 page
    And I should see empty Global Search results
    