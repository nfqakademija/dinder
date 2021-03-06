<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User extends BaseUser
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebook_id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="users")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="user")
     */
    private $items;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="previous")
     */
    private $previous_items;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="History", mappedBy="user")
     */
    private $histories;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Location", inversedBy="usersToMatch")
     * @ORM\JoinTable(name="users_locations")
     */
    private $locationsToMatch;

    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
        $this->previous_items = new ArrayCollection();
        $this->locationsToMatch = new ArrayCollection();
        $this->histories = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone(string $phone): User
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set location
     *
     * @param Location $location
     *
     * @return User
     */
    public function setLocation(Location $location = null): User
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return Location
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * Get items
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * Add locationsToMatch
     *
     * @param Location $locationToMatch
     *
     * @return User
     */
    public function addLocationsToMatch(Location $locationToMatch): User
    {
        $locationToMatch->addUsersToMatch($this);
        $this->locationsToMatch[ ] = $locationToMatch;

        return $this;
    }

    /**
     * Remove locationsToMatch
     *
     * @param Location $locationToMatch
     */
    public function removeLocationsToMatch(Location $locationToMatch): void
    {
        $this->locationsToMatch->removeElement($locationToMatch);
    }

    /**
     * Get locations
     *
     * @return Collection
     */
    public function getLocationsToMatch(): Collection
    {
        return $this->locationsToMatch;
    }

    /**
     * Get locationsToMatchArray
     *
     * @return array
     */
    public function getLocationsToMatchArray(): array
    {
        $result = [ ];

        foreach ($this->locationsToMatch as $location) {
            $result[ ] = $location->getId();
        }

        return $result;
    }

    /**
     * Add history
     *
     * @param History $history
     *
     * @return User
     */
    public function addHistory(History $history): User
    {
        $this->histories[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param History $history
     */
    public function removeHistory(History $history): void
    {
        $this->histories->removeElement($history);
    }

    /**
     * Get histories
     *
     * @return Collection
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId): User
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId(): ?string
    {
        return $this->facebook_id;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken): User
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken(): ?string
    {
        return $this->facebook_access_token;
    }

    /**
     * Add previousItem
     *
     * @param Item $previousItem
     *
     * @return User
     */
    public function addPreviousItem(Item $previousItem): User
    {
        $this->previous_items[] = $previousItem;

        return $this;
    }

    /**
     * Remove previousItem
     *
     * @param Item $previousItem
     */
    public function removePreviousItem(Item $previousItem): void
    {
        $this->previous_items->removeElement($previousItem);
    }

    /**
     * Get previousItems
     *
     * @return Collection
     */
    public function getPreviousItems(): Collection
    {
        return $this->previous_items;
    }
}
