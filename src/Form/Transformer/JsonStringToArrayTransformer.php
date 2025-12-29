<?php

namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Trasforma una stringa JSON in array e viceversa.
 */
class JsonStringToArrayTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function reverseTransform(mixed $value): ?array
    {
        if (null === $value || $value === '') {
            return null;
        }

        $decoded = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($decoded)) {
            throw new TransformationFailedException('Il valore deve essere un JSON oggetto/array.');
        }

        return $decoded;
    }
}
