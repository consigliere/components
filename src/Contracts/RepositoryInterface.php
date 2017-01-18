<?php

namespace Consigliere\Components\Contracts;

interface RepositoryInterface
{
    /**
     * Get all components.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get cached components.
     *
     * @return array
     */
    public function getCached();

    /**
     * Scan & get all available components.
     *
     * @return array
     */
    public function scan();

    /**
     * Get components as components collection instance.
     *
     * @return \Consigliere\Components\Collection
     */
    public function toCollection();

    /**
     * Get scanned paths.
     *
     * @return array
     */
    public function getScanPaths();

    /**
     * Get list of enabled components.
     *
     * @return mixed
     */
    public function enabled();

    /**
     * Get list of disabled components.
     *
     * @return mixed
     */
    public function disabled();

    /**
     * Get count from all components.
     *
     * @return int
     */
    public function count();

    /**
     * Get all ordered components.
     *
     * @return mixed
     */
    public function getOrdered();

    /**
     * Get components by the given status.
     *
     * @param int $status
     *
     * @return mixed
     */
    public function getByStatus($status);

    /**
     * Find a specific component.
     *
     * @param $name
     *
     * @return mixed
     */
    public function find($name);

    /**
     * Find a specific component. If there return that, otherwise throw exception.
     *
     * @param $name
     *
     * @return mixed
     */
    public function findOrFail($name);
}
