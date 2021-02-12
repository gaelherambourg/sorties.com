<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez donner un nom à votre Sortie")
     * @Assert\Length(
     *     min=4,
     *     minMessage="Le nom de la sortie doit comporter au moins 4 Caractères",
     *     max=30,
     *     maxMessage="Le nom de la sortie doit comporter moins de 30 caractères"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Veuillez selectionner une date")
     * @ORM\Column(type="datetime")
     */
    private $datedebut;

    /**
     * @Assert\Type(type="integer", message="La durée doit être un nombre")
     * @Assert\Positive(message="La durée doit être positive")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @Assert\NotBlank(message="Veuillez selectionner une date")
     * @ORM\Column(type="datetime")
     */
    private $datecloture;

    /**
     * @Assert\NotBlank(message="Veuillez précisez le nombre maximum de participant")
     * @Assert\Type(type="integer",message="Le nombre de place doit être un nombre")
     * @Assert\Positive(message="Le nombre de place doit être supérieur à 0 ")
     * @ORM\Column(type="integer")
     */
    private $nbinscriptionsmax;

    /**
     * @Assert\Length(max=500, maxMessage="Le nombre de caractères ne doit pas dépasser 500")
     * @ORM\Column(type="text", nullable=true, length=500)
     */
    private $descriptioninfos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etatsortie;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $urlPhoto;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="sorties")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $etats_no_etat;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieux_no_lieu;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDatecloture(): ?\DateTimeInterface
    {
        return $this->datecloture;
    }

    public function setDatecloture(\DateTimeInterface $datecloture): self
    {
        $this->datecloture = $datecloture;

        return $this;
    }

    public function getNbinscriptionsmax(): ?int
    {
        return $this->nbinscriptionsmax;
    }

    public function setNbinscriptionsmax(int $nbinscriptionsmax): self
    {
        $this->nbinscriptionsmax = $nbinscriptionsmax;

        return $this;
    }

    public function getDescriptioninfos(): ?string
    {
        return $this->descriptioninfos;
    }

    public function setDescriptioninfos(?string $descriptioninfos): self
    {
        $this->descriptioninfos = $descriptioninfos;

        return $this;
    }

    public function getEtatsortie(): ?int
    {
        return $this->etatsortie;
    }

    public function setEtatsortie(?int $etatsortie): self
    {
        $this->etatsortie = $etatsortie;

        return $this;
    }

    public function getUrlPhoto(): ?string
    {
        return $this->urlPhoto;
    }

    public function setUrlPhoto(?string $urlPhoto): self
    {
        $this->urlPhoto = $urlPhoto;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     *
     */
    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addSorty($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeSorty($this);
        }

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getEtatsNoEtat(): ?Etat
    {
        return $this->etats_no_etat;
    }

    public function setEtatsNoEtat(?Etat $etats_no_etat): self
    {
        $this->etats_no_etat = $etats_no_etat;

        return $this;
    }

    public function getLieuxNoLieu(): ?Lieu
    {
        return $this->lieux_no_lieu;
    }

    public function setLieuxNoLieu(?Lieu $lieux_no_lieu): self
    {
        $this->lieux_no_lieu = $lieux_no_lieu;

        return $this;
    }

    /**
     * @Assert\Callback
     **/
    public function isValidDateDebut(ExecutionContextInterface $context, $date)
    {
        $date = $this->getDatedebut();
        if($date < new \DateTime()){
            $context->buildViolation('La date doit être supérieur à la date du jour')
                ->atPath('datedebut')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     **/
    public function isValidDateCloture(ExecutionContextInterface $context, $date)
    {
        $date = $this->getDatecloture();
        if($date < new \DateTime() || $date > $this->getDatedebut()){
            $context->buildViolation('La date doit être supérieure à la date du jour et inférieure à la date de la sortie')
                ->atPath('datecloture')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     */
    public function validationInscription(ExecutionContextInterface $context)
    {
        if($this->getDatecloture()< new \DateTime()){
            $context->buildViolation('Les inscriptions sont closes')
                ->atPath('addParticipant')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback
     */
    public function validationDesistement(ExecutionContextInterface $context)
    {
        if($this->getEtatsNoEtat() == 4){
            $context->buildViolation('Vous ne pouvez pas vous désister. La sortie est en cours')
                ->atPath('removeParticipant')
                ->addViolation();
        }
    }

}
