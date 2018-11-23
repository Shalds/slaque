<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use ICanBoogie\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupeRepository")
 */
class Groupe implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="groupes")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="groupe")
     */
    private $messages;

    private $idlastIdMessage;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function getUserUsername()
    {
        $tabUsername =[];

        foreach ($this->getUser() as $item){
            $tabUsername[] = $item->getUsername();
        }

        return $tabUsername;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }


    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        if($this->getIdlastIdMessage() != null){

            $IdlastIdMessage = $this->getIdlastIdMessage();

            $criteria = Criteria::create()
                ->andWhere(Criteria::expr()->gt('id', $IdlastIdMessage));

            return $this->messages->matching($criteria);

        }else{
            return $this->messages;
        }
    }

    public function getMessagesJS()
    {
        $messageObj = $this->getMessages();
        $tabMessage = [];

        foreach ($messageObj as $messages){

            $tabMessage[] = ['id' => $messages->getId(), 'message' => $messages->getText(), 'date' => $messages->getDateCreated(), 'author' => $messages->getUser()->getUsername()];
        }
        return $tabMessage;

    }


    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setGroupe($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getGroupe() === $this) {
                $message->setGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdlastIdMessage()
    {
        return $this->idlastIdMessage;
    }

    /**
     * @param mixed $idlastIdMessage
     */
    public function setIdlastIdMessage($idlastIdMessage): void
    {
        $this->idlastIdMessage = $idlastIdMessage;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return ["name" => $this->getName(), "id" => $this->getId(), "messages" => $this->getMessagesJS(), "userName" => $this->getUserUsername()];
    }
}
