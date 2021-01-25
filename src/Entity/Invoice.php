<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *     "pagination_enabled": true,
 *     "order": {"amount": "desc"},
 *     },
 *     normalizationContext={
 *          "groups"={"invoice_listing:read"},
 *     },
 *     denormalizationContext={
 *          "groups"={"invoice:write"},
 *     },
 *     subresourceOperations={
 *          "api_customers_invoices_get_subresource"={
 *                  "method"={"GET"},
 *                  "normalization_context"={"groups"={ "customer_invoice_listing:read" }},
 *          }
 *     },
 * )
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"invoice_listing:read", "customer_invoice_listing:read" })
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoice_listing:read", "customer_invoice_listing:read", "invoice:write" })
     * @Assert\NotBlank(message="Amount can not be empty.")
     * @Assert\Type(type="numeric", message="Amount should be a valid numeric value.")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"invoice_listing:read", "invoice:write"})
     */
    private $sentAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"invoice_listing:read", "customer_invoice_listing:read", "invoice:write" })
     * @ApiFilter(SearchFilter::class)
     * @Assert\Choice({"SENT", "CANCELLED", "PAID"}, message="Status must be SENT, CANCELLED or PAID.")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"invoice_listing:read", "invoice:write" })
     * @Assert\NotBlank()
     */
    private $customer;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"invoice_listing:read", "customer_invoice_listing:read", "invoice:write" })
     * @Assert\NotBlank
     * @Assert\Type(type="long")
     */
    private $reference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
