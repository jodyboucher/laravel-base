<?php

namespace JodyBoucher\Laravel\Test\Eloquent;

use JodyBoucher\Laravel\Eloquent\FilteringTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class FilteringTraitTest extends TestCase
{
    use FilteringTrait;

    protected $model;
    protected $builder;
    protected $filters;

    protected function setUp()
    {
        $this->model = new UserModelStub();
        $this->builder = Mockery::mock('Illuminate\Database\Eloquent\Builder');
        $this->filters = [];
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testFilterBetween()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => [1, 10],
            'operator' => 'bt',
            'not' => false
        ];

        $expectedParams = ['user.id', [1, 10]];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('whereBetween')->once()->with($expectedParams[0], $expectedParams[1]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterContains()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'contain',
            'not' => false
        ];

        $expectedParams = ['CAST(user.name AS TEXT)', 'ILIKE', '%ABC%'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterDoesNotContain()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'contain',
            'not' => true
        ];

        $expectedParams = ['CAST(user.name AS TEXT)', 'NOT ILIKE', '%ABC%'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterDoesNotEndWith()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'end',
            'not' => true
        ];

        $expectedParams = ['CAST(user.name AS TEXT)', 'NOT ILIKE', '%ABC'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterDoesNotEqual()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'eq',
            'not' => true
        ];

        $expectedParams = ['user.name', '!=', 'ABC'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterDoesNotStartWith()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'start',
            'not' => true
        ];

        $expectedParams = ['CAST(user.name AS TEXT)', 'NOT ILIKE', 'ABC%'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterEndsWith()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'end',
            'not' => false
        ];

        $expectedParams = ['CAST(user.name AS TEXT)', 'ILIKE', '%ABC'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterEquals()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'eq',
            'not' => false
        ];

        $expectedParams = ['user.name', '=', 'ABC'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterGreaterThen()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => 123,
            'operator' => 'gt',
            'not' => false
        ];

        $expectedParams = ['user.id', '>', 123];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterGreaterThenEqual()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => 123,
            'operator' => 'gte',
            'not' => false
        ];

        $expectedParams = ['user.id', '>=', '123'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterIn()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => [1, 2, 3],
            'operator' => 'in',
            'not' => false
        ];

        $expectedParams = ['user.id', [1, 2, 3]];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('whereIn')->once()->with($expectedParams[0], $expectedParams[1]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterLessThen()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => 123,
            'operator' => 'lt',
            'not' => false
        ];

        $expectedParams = ['user.id', '<', 123];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterLessThenEqual()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => 123,
            'operator' => 'lte',
            'not' => false
        ];

        $expectedParams = ['user.id', '<=', '123'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNoKey()
    {
        // Empty key
        $this->filters[] = [
            'key' => '',
            'value' => "ABC",
            'operator' => 'eq',
            'not' => false
        ];

        $this->builder->shouldNotReceive('getModel');
        $this->builder->shouldNotReceive('where');

        $this->applyFilters($this->builder, $this->filters);

        // null key
        $this->filters = [];
        $this->filters[] = [
            'key' => null,
            'value' => "ABC",
            'operator' => 'eq',
            'not' => false
        ];
        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNotBetween()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => [1, 10],
            'operator' => 'bt',
            'not' => true
        ];

        $expectedParams = ['user.id', [1, 10]];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('whereNotBetween')->once()->with($expectedParams[0], $expectedParams[1]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNotGreaterThen()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => 123,
            'operator' => 'gt',
            'not' => true
        ];

        $expectedParams = ['user.id', '<=', 123];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNotGreaterThenEqual()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => 123,
            'operator' => 'gte',
            'not' => true
        ];

        $expectedParams = ['user.id', '<', '123'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNotIn()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => [1, 2, 3],
            'operator' => 'in',
            'not' => true
        ];

        $expectedParams = ['user.id', [1, 2, 3]];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('whereNotIn')->once()->with($expectedParams[0], $expectedParams[1]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNotLessThen()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => 123,
            'operator' => 'lt',
            'not' => true
        ];

        $expectedParams = ['user.id', '>=', 123];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNotLessThenEqual()
    {
        $this->filters[] = [
            'key' => 'id',
            'value' => 123,
            'operator' => 'lte',
            'not' => true
        ];

        $expectedParams = ['user.id', '>', '123'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNotNull()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'null',
            'not' => true
        ];

        $expectedParams = ['user.name'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('whereNotNull')->once()->with($expectedParams[0]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterNull()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'null',
            'not' => false
        ];

        $expectedParams = ['user.name'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('whereNull')->once()->with($expectedParams[0]);

        $this->applyFilters($this->builder, $this->filters);
    }

    public function testFilterStartsWith()
    {
        $this->filters[] = [
            'key' => 'name',
            'value' => 'ABC',
            'operator' => 'start',
            'not' => false
        ];

        $expectedParams = ['CAST(user.name AS TEXT)', 'ILIKE', 'ABC%'];

        $this->builder->shouldReceive('getModel')->once()->andReturn($this->model);
        $this->builder->shouldReceive('where')->once()->with($expectedParams[0], $expectedParams[1],
            $expectedParams[2]);

        $this->applyFilters($this->builder, $this->filters);
    }
}
