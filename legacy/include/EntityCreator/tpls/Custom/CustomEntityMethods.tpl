    
    
    public function getMainEntity()
    {
        return $this->mainEntity;
    }

    public function setMainEntity($mainEntity)
    {
        $this->mainEntity = $mainEntity;

        if ($mainEntity && $mainEntity->getCustomEntity() !== $this) {
            $mainEntity->setCustomEntity($this);
        }

        return $this;
    }

