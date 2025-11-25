    
    
    
    public function getCustomEntity()
    {
        return $this->customEntity;
    }


    public function setCustomEntity($customEntity)
    {
        $this->customEntity = $customEntity;

        if ($customEntity && $customEntity->getMainEntity() !== $this) {
            $customEntity->setMainEntity($this);
        }

        return $this;
    }

    