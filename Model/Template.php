<?php

namespace IDCI\Bundle\DocumentManagementBundle\Model;

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
    private $slug;

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
     * @var array<TemplateData>
     */
    private $templateData;

    public function __construct()
    {
        $this->templateData = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
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
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug.
     *
     * @param string slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Add templateData.
     *
     * @param TemplateData $templateData
     *
     * @return Template
     */
    public function addTemplateData(TemplateData $templateData)
    {
        $this->templateData->add($templateData);

        return $this;
    }

    /**
     * Remove templateData.
     *
     * @param TemplateData $templateData
     *
     * @return Template
     */
    public function removeTemplateData(TemplateData $templateData)
    {
        $this->templateData->removeElement($templateData);

        return $this;
    }

    /**
     * Get templateData.
     *
     * @return ArrayCollection
     */
    public function getTemplateData()
    {
        return $this->templateData;
    }

    /**
     * Set templateData.
     *
     * @param templateData the value to set.
     */
    public function setTemplateData($templateData)
    {
        $this->templateData = $templateData;

        return $this;
    }
}

