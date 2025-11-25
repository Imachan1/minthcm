{literal}
    /**
     * @ORM\OneToOne(
     *     targetEntity="{/literal}{$className}{literal}_cstm",
     *     mappedBy="mainEntity",
     *     cascade={"persist", "remove"},
     *     fetch="EAGER"
     * )
     */
    private $customEntity;
{/literal}