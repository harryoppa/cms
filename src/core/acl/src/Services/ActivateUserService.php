<?php

namespace TVHung\ACL\Services;

use TVHung\ACL\Models\User;
use TVHung\ACL\Repositories\Interfaces\ActivationInterface;
use InvalidArgumentException;

class ActivateUserService
{
    /**
     * @var ActivationInterface
     */
    protected $activationRepository;

    /**
     * ActivateUserService constructor.
     * @param ActivationInterface $activationRepository
     */
    public function __construct(ActivationInterface $activationRepository)
    {
        $this->activationRepository = $activationRepository;
    }

    /**
     * Activates the given user.
     *
     * @param User $user
     * @return bool
     * @throws InvalidArgumentException
     */
    public function activate($user)
    {
        if (!$user instanceof User) {
            throw new InvalidArgumentException('No valid user was provided.');
        }

        if ($this->activationRepository->completed($user)) {
            return false;
        }

        event('acl.activating', $user);

        $activation = $this->activationRepository->createUser($user);

        event('acl.activated', [$user, $activation]);

        return $this->activationRepository->complete($user, $activation->code);
    }
}
