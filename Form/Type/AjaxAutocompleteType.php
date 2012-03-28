<?

namespace Shtumi\UsefulBundle\Form\Type;

use Gregwar\FormBundle\Type\EntityIdType;

use Symfony\Component\Form\FormBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Exception\FormException;

class AjaxAutocompleteType extends EntityIdType
{

    private $container;

    public function __construct($container)
    {

        parent::__construct($container->get('doctrine'));

        $this->container = $container;

    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'entity_alias'      => null,
            'em'                => null,
            'class'             => null,
            'property'          => null,
            'query_builder'     => null,
            //'type'              => 'hidden',
            'hidden'            => false,
        );

        $options = array_replace($defaultOptions, $options);


        $entities = $this->container->getParameter('shtumi.autocomplete_entities');

        if (null === $options['entity_alias']) {
            throw new FormException('You must provide a entity alias "entity_alias" and tune it in config file');
        }

        if (!isset ($entities[$options['entity_alias']])){
            throw new FormException('There are no entity alias "' . $options['entity_alias'] . '" in your config file');
        }

        $options['class'] = $entities[$options['entity_alias']]['class'];
        $options['property'] = $entities[$options['entity_alias']]['property'];

        return $options;
    }

    public function getName()
    {
        return 'type_ajax_autocomplete';
    }

    public function getParent(array $options)
    {
        return 'text';
    }

    public function buildForm(FormBuilder $builder, array $options)
    {

        parent::buildForm($builder, $options);

        $builder->setAttribute('entity_alias', $options['entity_alias']);
    }

    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('entity_alias',  $form->getAttribute('entity_alias'));
    }

}