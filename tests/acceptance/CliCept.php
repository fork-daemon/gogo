<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('perform "cli-codeception" actions and see result');
$I->runShellCommand('./cli-codeception');
$I->seeInShellOutput('Start with empty argv');
$I->seeInShellOutput('Finish with empty argv');