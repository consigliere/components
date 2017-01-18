<?php

namespace Consigliere\Components\Publishing;

use Consigliere\Components\Migrations\Seeder;

class SeedPublisher extends AssetPublisher
{
    /**
     * @var Seeder
     */
    private $seeder;

    /**
     * MigrationPublisher constructor.
     *
     * @param Seeder $seeder
     */
    public function __construct(Seeder $seeder)
    {
        $this->seeder = $seeder;
        parent::__construct($seeder->getComponent());
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        return $this->repository->config('paths.seed');
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->seeder->getPath();
    }
}
