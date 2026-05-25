<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Cart
{
    #[ORM\Id]
    // On retire GeneratedValue car c'est toi qui va fournir l'ID (le string)
    #[ORM\Column(type: "string", length: 255)] 
    private ?string $id = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\OneToMany(
    mappedBy: "cart",
    targetEntity: CartItem::class,
    cascade: ["persist", "remove"],
    orphanRemoval: true
    )]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    // Le getter retourne maintenant un string
    public function getId(): ?string
    {
        return $this->id;
    }

    // Le setter accepte maintenant un string
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $item): self
    {
        if (!$this->cartItems->contains($item)) {
            $this->cartItems[] = $item;
            $item->setCart($this); // VERY IMPORTANT
        }

        return $this;
    }
    public function removeCartItem(CartItem $item): self
    {
        if ($this->cartItems->removeElement($item)) {

            // optionnel si relation bidirectionnelle
            if ($item->getCart() === $this) {
                $item->setCart(null);
            }
        }

        return $this;
    }

    public function total(): float
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            // Rappel : assure-toi que getPrice() existe dans CartItem
            $total += $item->getPrice() * $item->getQuantity();
        }
        return $total;
    }
}