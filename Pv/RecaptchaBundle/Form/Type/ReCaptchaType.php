<?php
/*
* This file is part of the DSReCaptcha Component.
*
* (c) Ilya Pokamestov
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Pv\RecaptchaBundle\Form\Type;

use Pv\RecaptchaBundle\Validator\ReCaptchaConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Intl\Locale\Locale;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type for reCaptcha.
 *
 * (@author Ilya Pokamestov <dario_swain@yahoo.com>)
 * @author Pierre Vassoilles <pierre.vassoilles@gmail.com>
 */
class ReCaptchaType extends AbstractType
{
    const JS_API_URL = 'https://www.google.com/recaptcha/api.js';

    /** @var  string */
    protected $publicKey;

    /** @var  string */
    protected $locale;

    public function __construct($publicKey, $locale = null)
    {
        if (null === $publicKey) {
            throw new InvalidConfigurationException('The parameters "public_key" must be configured.');
        }

        $this->publicKey = $publicKey;

        if (null !== $locale) {
            $this->locale = $locale;
        } else {
            $this->locale = Locale::getDefault();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace(
            $view->vars,
            array(
                'public_key'        => $this->publicKey,
                'lang'              => $this->locale,
                'js_api_url'        => self::JS_API_URL,
                'captcha_invisible' => $options['invisible'],
                'form_id'           => $options['form_id'],
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(
                array(
                    'constraints'      => array(new ReCaptchaConstraint()),
                    'invisible'        => false,
                    'form_id'          => '',
                )
            )
            ->addAllowedTypes(
                array(
                    'form_id'          => 'string',
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pv_recaptcha';
    }
}
