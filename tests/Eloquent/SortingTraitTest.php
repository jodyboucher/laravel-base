<?php

namespace JodyBoucher\Laravel\Test\Eloquent;

use JodyBoucher\Laravel\Eloquent\SortingTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class SortingTraitTest extends TestCase
{
    use SortingTrait;

    protected $builder;
    protected $sorts;

    protected function setUp()
    {
        $this->builder = Mockery::mock('Illuminate\Database\Eloquent\Builder');
        $this->sorts = [];
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testSortAscending()
    {
        $this->sorts[] = [
            'column' => 'id',
            'direction' => 'asc'
        ];

        $expectedParams = ['id', 'ASC'];

        $this->builder->shouldReceive('orderBy')->once()->with($expectedParams[0], $expectedParams[1]);

        $this->applySorting($this->builder, $this->sorts);
    }

    public function testSortDescending()
    {
        $this->sorts[] = [
            'column' => 'id',
            'direction' => 'desc'
        ];

        $expectedParams = ['id', 'DESC'];

        $this->builder->shouldReceive('orderBy')->once()->with($expectedParams[0], $expectedParams[1]);

        $this->applySorting($this->builder, $this->sorts);
    }

    public function testSortMultiple()
    {
        $this->sorts[] = [
            'column' => 'lastName',
            'direction' => 'asc'
        ];
        $this->sorts[] = [
            'column' => 'firstName',
            'direction' => 'asc'
        ];
        $this->sorts[] = [
            'column' => 'dob',
            'direction' => 'desc'
        ];

        $this->builder
            ->shouldReceive('orderBy')->once()->with('lastName', 'ASC')
            ->shouldReceive('orderBy')->once()->with('firstName', 'ASC')
            ->shouldReceive('orderBy')->once()->with('dob', 'DESC');

        $this->applySorting($this->builder, $this->sorts);
    }

    public function testSortUnwrappedSingle()
    {
        $this->sorts = ['id'];

        $expectedParams = ['id', 'ASC'];

        $this->builder->shouldReceive('orderBy')->once()->with($expectedParams[0], $expectedParams[1]);

        $this->applySorting($this->builder, $this->sorts);
    }

    public function testSortUnwrappedMultiple()
    {
        $this->sorts = ['lastName', 'firstName', 'dob'];

        $this->builder
            ->shouldReceive('orderBy')->once()->with('lastName', 'ASC')
            ->shouldReceive('orderBy')->once()->with('firstName', 'ASC')
            ->shouldReceive('orderBy')->once()->with('dob', 'ASC');

        $this->applySorting($this->builder, $this->sorts);
    }
}
