<?php

namespace MMC\Profile\Component\Browser;

use Symfony\Component\Serializer\Annotation\Groups;

class BrowserResponse
{
    /**
     * @Groups({"browse"})
     */
    private $items;

    /**
     * @Groups({"browse"})
     */
    private $currentPage;

    /**
     * @Groups({"browse"})
     */
    private $nbResults;

    /**
     * @Groups({"browse"})
     */
    private $nbPerPage;

    /**
     * @Groups({"browse"})
     */
    private $nbPages;

    public function __construct(
        $items,
        $currentPage,
        $nbResults,
        $nbPerPage,
        $nbPages
    ) {
        $this->items = $items;
        $this->currentPage = $currentPage;
        $this->nbResults = $nbResults;
        $this->nbPerPage = $nbPerPage;
        $this->nbPages = $nbPages;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getNbResults()
    {
        return $this->nbResults;
    }

    public function getNbPerPage()
    {
        return $this->nbPerPage;
    }

    public function getNbPages()
    {
        return $this->nbPages;
    }
}
