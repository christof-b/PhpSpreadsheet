<?php

namespace PhpOffice\PhpSpreadsheetTests\Worksheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PHPUnit_Framework_TestCase;

class RowIteratorTest extends PHPUnit_Framework_TestCase
{
    public $mockWorksheet;
    public $mockRow;

    public function setUp()
    {
        $this->mockRow = $this->getMockBuilder(Row::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockWorksheet = $this->getMockBuilder(Worksheet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockWorksheet->expects($this->any())
            ->method('getHighestRow')
            ->will($this->returnValue(5));
    }

    public function testIteratorFullRange()
    {
        $iterator = new RowIterator($this->mockWorksheet);
        $rowIndexResult = 1;
        self::assertEquals($rowIndexResult, $iterator->key());

        foreach ($iterator as $key => $row) {
            self::assertEquals($rowIndexResult++, $key);
            self::assertInstanceOf(Row::class, $row);
        }
    }

    public function testIteratorStartEndRange()
    {
        $iterator = new RowIterator($this->mockWorksheet, 2, 4);
        $rowIndexResult = 2;
        self::assertEquals($rowIndexResult, $iterator->key());

        foreach ($iterator as $key => $row) {
            self::assertEquals($rowIndexResult++, $key);
            self::assertInstanceOf(Row::class, $row);
        }
    }

    public function testIteratorSeekAndPrev()
    {
        $iterator = new RowIterator($this->mockWorksheet, 2, 4);
        $columnIndexResult = 4;
        $iterator->seek(4);
        self::assertEquals($columnIndexResult, $iterator->key());

        for ($i = 1; $i < $columnIndexResult - 1; ++$i) {
            $iterator->prev();
            self::assertEquals($columnIndexResult - $i, $iterator->key());
        }
    }

    /**
     * @expectedException \PhpOffice\PhpSpreadsheet\Exception
     */
    public function testSeekOutOfRange()
    {
        $iterator = new RowIterator($this->mockWorksheet, 2, 4);
        $iterator->seek(1);
    }

    /**
     * @expectedException \PhpOffice\PhpSpreadsheet\Exception
     */
    public function testPrevOutOfRange()
    {
        $iterator = new RowIterator($this->mockWorksheet, 2, 4);
        $iterator->prev();
    }
}
