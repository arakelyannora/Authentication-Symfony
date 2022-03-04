<?php

namespace App\Configuration;

use App\Exceptions\ValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DataTransferObjectConverter implements ParamConverterInterface
{
	public function __construct(
		private DenormalizerInterface $denormalizer,
		private ValidatorInterface $validator
	) {
	}

	/**
	 * @param Request        $request
	 * @param ParamConverter $configuration
	 *
	 * @return bool|void
	 * @throws ValidationException
	 * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
	 */
	public function apply(Request $request, ParamConverter $configuration)
	{
		$data = json_decode($request->getContent(), true);

		$this->checkIfJsonIsValid();

		try {
			$dto = $this->denormalizer->denormalize($data, $configuration->getClass());
		} catch (\RuntimeException) {
			throw ValidationException::errors(["Invalid data provided"]);
		}

		$validationErrors = $this->validator->validate($dto);

		if ($validationErrors->count() > 0) {
			$errors = [];
			foreach ($validationErrors as $error) {
				$errors[] = $error->getMessage();
			}
			throw ValidationException::errors($errors);
		}

		$request->attributes->set($configuration->getName(), $dto);

		return true;
	}

	public function supports(ParamConverter $configuration): bool
	{
		return class_exists($configuration->getClass());
	}

	/**
	 * @throws ValidationException
	 */
	private function checkIfJsonIsValid(): void
	{
		if (json_last_error() != JSON_ERROR_NONE) {
			throw ValidationException::errors(["Invalid json provided"]);
		};
	}
}