<?php

namespace IDCI\Bundle\DocumentManagementBundle\Model;

use Behat\Transliterator\Transliterator;
use Doctrine\Common\Collections\ArrayCollection;

class Template
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $html;

    /**
     * @var string
     */
    private $css;

    /**
     * @var \Datetime
     */
    private $createdAt;

    /**
     * @var \Datetime
     */
    private $updatedAt;

    /**
     * @var array<Document>
     */
    private $documents;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    /**
     * On create.
     */
    public function onCreate()
    {
        $now = new \DateTime("now");
        $this
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
    }

    /**
     * On update.
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new \DateTime("now"));
    }

    /**
     * toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get Uuid.
     *
     * @return Uuid
     */
    public function getUuid()
    {
        return $this->id;
    }
    /**
     * Get id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id->toString();
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string name
     *
     * @return Template
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string description
     *
     * @return Template
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get html.
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set html.
     *
     * @param string html
     *
     * @return Template
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get css.
     *
     * @return string
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Set css.
     *
     * @param string css
     *
     * @return Template
     */
    public function setCss($css)
    {
        $this->css = $css;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt.
     *
     * @param \Datetime createdAt
     *
     * @return Template
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \Datetime updatedAt
     *
     * @return Template
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Add document.
     *
     * @param Document $document
     *
     * @return Template
     */
    public function addDocument(Document $document)
    {
        $this->documents->add($document);

        return $this;
    }

    /**
     * Remove document.
     *
     * @param Document $document
     *
     * @return Template
     */
    public function removeDocument(Document $document)
    {
        $this->documents->removeElement($document);

        return $this;
    }

    /**
     * Get documents.
     *
     * @return ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}

