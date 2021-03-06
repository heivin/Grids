<?php
namespace Nayjest\Grids;

class Sorter
{
    /**
     * @var Grid
     */
    protected $grid;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    public function link(FieldConfig $column, $direction)
    {
        return (new GridInputProcessor($this->grid))
            ->setSorting($column, $direction)
            ->getUrl();
    }

    public function apply()
    {
        $input = $this->grid->getInputProcessor()->getInput();
        $sort = null;
        if (isset($input['sort'])) {
            foreach ($input['sort'] as $field => $direction) {
                $sort = [$field, $direction];
                break;
            }
        }
        foreach ($this->grid->getConfig()->getColumns() as $column) {
            if ($sort) {
                if ($column->getName() === $sort[0]) {
                    $column->setSorting($sort[1]);
                } else {
                    $column->setSorting(null);
                }
            } else {
                if ($direction = $column->getSorting()) {
                    $sort = [$column->getName(), $direction];
                }
            }
        }
        if ($sort) {
            $this
                ->grid
                ->getConfig()
                ->getDataProvider()
                ->orderBy($sort[0], $sort[1]);
        }
    }
}
