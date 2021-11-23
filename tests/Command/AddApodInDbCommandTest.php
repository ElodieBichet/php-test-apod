<?php

namespace App\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddApodInDbCommandTest extends KernelTestCase
{
    public function testExecuteWithoutDate()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $command = $application->find('app:add-apod-in-db');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'date' => null
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("The picture of the day " . date('Y-m-d') . " has been added to the database", $output);
    }

    public function testExecuteWithDate()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $command = $application->find('app:add-apod-in-db');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'date' => '2021-11-20'
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("The picture of the day 2021-11-20 has been added to the database", $output);
    }
}
