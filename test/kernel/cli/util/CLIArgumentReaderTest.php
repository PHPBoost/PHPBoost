<?php

class CLIArgumentsReaderTest extends PHPBoostUnitTestCase
{
	public function test_get_nb_args_no_args()
	{
		$arg_reader = new CLIArgumentsReader(array());
		self::assertEquals(0, $arg_reader->get_nb_args());
	}

	public function test_get_nb_args_multiples_args()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		self::assertEquals(5, $arg_reader->get_nb_args());
	}

	public function test_get_arg_at()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		self::assertEquals('3', $arg_reader->get_arg_at(2));
	}

	public function test_get_arg_at_out_of_bound()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		try
		{
			$arg_reader->get_arg_at(7);
			self::assertTrue(false);
		}
		catch (Exception $ex)
		{
			self::assertTrue($ex instanceof OutOfBoundsException);
		}
	}

	public function test_has_arg()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		self::assertTrue($arg_reader->has_arg('2'));
	}

	public function test_has_arg_false()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		self::assertFalse($arg_reader->has_arg('42'));
	}

	public function test_find_arg_index()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		self::assertEquals(3, $arg_reader->find_arg_index('6'));
	}

	public function test_find_arg_index_false()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		try
		{
			$arg_reader->find_arg_index('42');
			self::assertTrue(false);
		}
		catch (Exception $ex)
		{
			self::assertTrue($ex instanceof ArgumentNotFoundException);
		}
	}

    public function test_get()
    {
        $arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
        self::assertEquals('12', $arg_reader->get('6'));
    }

    public function test_get_default_value()
    {
        $arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
        self::assertEquals('12', $arg_reader->get('6', 'coucou'));
    }

	public function test_get_not_found()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		try
		{
			$arg_reader->get('42');
			self::assertTrue(false);
		}
		catch (Exception $ex)
		{
			self::assertTrue($ex instanceof ArgumentNotFoundException);
		}
	}

	public function test_get_not_found_default_value()
	{
		$arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
		self::assertEquals('coucou', $arg_reader->get('42', 'coucou'));
	}

    public function test_get_out_of_bounds()
    {
        $arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
        try
        {
            $arg_reader->get('12');
            self::assertTrue(false);
        }
        catch (Exception $ex)
        {
            self::assertTrue($ex instanceof ArgumentNotFoundException);
        }
    }

    public function test_get_out_of_bounds_default_value()
    {
        $arg_reader = new CLIArgumentsReader(array('1', '2', '3', '6', '12'));
        self::assertEquals('coucou', $arg_reader->get('12', 'coucou'));
    }
}
?>