<?php

namespace Tests\Louvre\ReservationBundle\Services\CodeGenerator;

use Louvre\ReservationBundle\Services\CodeGenerator\LouvreCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvreCodeGeneratorTest extends WebTestCase
{
	private $codeGenerator;

	protected function setUp()
	{
		parent::setUp();

		$this->codeGenerator = new LouvreCodeGenerator();
	}

	/** @test */
	public function serviceShouldGenerateParticularString()
	{
		$testCode1 = $this->codeGenerator->generateRandomCode();
		$testCode2 = $this->codeGenerator->generateRandomCode();
		$testCode3 = $this->codeGenerator->generateRandomCode();	

		// Test if testCode contains a code begining by "LR-" and containing 30 random caracters composed by only capital letters and numbers
		$this->assertTrue((preg_match('/LR-[A-Z0-9]{30}/', $testCode1) == 1) ? true : false);
		$this->assertTrue((preg_match('/LR-[A-Z0-9]{30}/', $testCode2) == 1) ? true : false);
		$this->assertTrue((preg_match('/LR-[A-Z0-9]{30}/', $testCode3) == 1) ? true : false);
	}
}
