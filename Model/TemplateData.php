<?php

namespace IDCI\Bundle\DocumentManagementBundle\Model;

class TemplateData
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var \Datetime
     */
    private $createdAt;

    /**
     * @var \Datetime
     */
    private $updatedAt;

    /**
     * @var Template
     */
    private $template;

    /**
     * Get id.
     *
     * @return Uuid.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data.
     *
     * @param array data
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get reference.
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set reference.
     *
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

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
     * @param \DateTime createdAt
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
     * @param \Datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get template.
     *
     * @return Template.
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set template.
     *
     * @param Template $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }
}

