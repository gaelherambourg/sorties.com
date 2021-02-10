<?php


namespace App\Data;


class SearchData
{

    /**
     * @var string
     */
    private $campus ='';

    /**
     * @var string
     */
    private $recherche ='';

    /**
     * @return string
     */
    public function getRecherche(): ?string
    {
        return $this->recherche;
    }

    /**
     * @param string $recherche
     */
    public function setRecherche(?string $recherche): void
    {
        $this->recherche = $recherche;
    }

    /**
     *
     */
    private $datedeb;

    /**
     * @return mixed
     */
    public function getDatedeb()
    {
        return $this->datedeb;
    }

    /**
     * @param mixed $datedeb
     */
    public function setDatedeb($datedeb): void
    {
        $this->datedeb = $datedeb;
    }

    /**
     * @return mixed
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * @param mixed $datefin
     */
    public function setDatefin($datefin): void
    {
        $this->datefin = $datefin;
    }

    /**
     *
     */
    private $datefin;


    /**
     * @var boolean
     */
    private $organisateur =false;

    /**
     * @var boolean
     */
    private $inscrit = false;

    /**
     * @var boolean
     */
    private  $pasInscrit = false;

    /**
     * @var boolean
     */
    private $passees = false;

    /**
     * @return string
     */
    public function getCampus(): string
    {
        return $this->campus;
    }

    /**
     * @param string $campus
     */
    public function setCampus(string $campus): void
    {
        $this->campus = $campus;
    }



    /**
     * @return bool
     */
    public function isOrganisateur(): bool
    {
        return $this->organisateur;
    }

    /**
     * @param bool $organisateur
     */
    public function setOrganisateur(bool $organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    /**
     * @return bool
     */
    public function isInscrit(): bool
    {
        return $this->inscrit;
    }

    /**
     * @param bool $inscrit
     */
    public function setInscrit(bool $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    /**
     * @return bool
     */
    public function isPasInscrit(): bool
    {
        return $this->pasInscrit;
    }

    /**
     * @param bool $pasInscrit
     */
    public function setPasInscrit(bool $pasInscrit): void
    {
        $this->pasInscrit = $pasInscrit;
    }

    /**
     * @return bool
     */
    public function isPassees(): bool
    {
        return $this->passees;
    }

    /**
     * @param bool $passees
     */
    public function setPassees(bool $passees): void
    {
        $this->passees = $passees;
    }


}





