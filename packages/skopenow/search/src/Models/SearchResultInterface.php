<?php

namespace Skopenow\Search\Models;

interface SearchResultInterface
{
    public function __construct(string $url, $isProfile = null);

    public function clearProfileInfo(string $type = "");

    public function addName(\App\DataTypes\Name $name): self;

    public function addLocation(\App\DataTypes\Address $location): self;

    public function addEducation(\App\DataTypes\School $education): self;

    public function addExperience(\App\DataTypes\Work $experience): self;

    public function addAge(\App\DataTypes\Age $age): self;

    public function addEmail(\App\DataTypes\Email $email): self;

    public function addPhone(\App\DataTypes\Phone $phone): self;

    public function addWebsite(\App\DataTypes\Website $website): self;

    /**
     * @return \Iterator
     */
    public function getNames(): \Iterator;

    /**
     * @return \Iterator
     */
    public function getLocations(): \Iterator;

    /**
     * @return \Iterator
     */
    public function getExperiences(): \Iterator;

    /**
     * @return \Iterator
     */
    public function getEducations(): \Iterator;

    /**
     * @return bool
     */
    public function getIsProfile();

    /**
     * @param bool $isProfile
     *
     * @return self
     */
    public function setIsProfile($isProfile);

    /**
     * @return bool
     */
    public function getIsRelative();

    /**
     * @param bool $isRelative
     *
     * @return self
     */
    public function setIsRelative($isRelative);

    /**
     * @return bool
     */
    public function getIsPrimary();

    /**
     * @param bool $isPrimary
     *
     * @return self
     */
    public function setIsPrimary($isPrimary);

    /**
     * @return float
     */
    public function getScore(): float;

    /**
     * @param float $score
     *
     * @return self
     */
    public function setScore($score);

    /**
     * @return mixed
     */
    public function getMatchStatus();

    /**
     * @param mixed $matchStatus
     *
     * @return self
     */
    public function setMatchStatus($matchStatus);

    public function save(): bool;

    public function getLinks();
    public function addLink($link): self;
    public function setLinks($links): self;
}
