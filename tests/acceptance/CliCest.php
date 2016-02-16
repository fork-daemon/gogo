<?php


class CliCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTestWithoutArguments(AcceptanceTester $I)
    {
        $I->runShellCommand('./cli-codeception');
        $I->seeInShellOutput('Start with empty argv');
        $I->seeInShellOutput('Finish with empty argv');
    }

    public function tryToTestSleep(AcceptanceTester $I)
    {
        $I->runShellCommand('./cli-codeception sleep');
        $I->seeInShellOutput('Start sleep 1 seconds');
        $I->seeInShellOutput('Finish sleep 1 seconds');
    }

    public function tryToTestItems(AcceptanceTester $I)
    {
        $I->runShellCommand('./cli-codeception items');
        $I->seeInShellOutput('Start items');
        $I->seeInShellOutput('items 5 exists');
        $I->seeInShellOutput('Finish items');
    }
}
