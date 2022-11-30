<?php

namespace TVHung\ACL\Commands;

use TVHung\ACL\Repositories\Interfaces\UserInterface;
use TVHung\ACL\Services\ActivateUserService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:user:create', 'Create a super user')]
class UserCreateCommand extends Command
{
    public function handle(UserInterface $userRepository, ActivateUserService $activateUserService): int
    {
        $this->info('Creating a super user...');

        try {
            $user = $userRepository->getModel();
            $user->first_name = $this->askWithValidate('Enter first name', 'required|min:2|max:60');
            $user->last_name = $this->askWithValidate('Enter last name', 'required|min:2|max:60');
            $user->email = $this->askWithValidate('Enter email address', 'required|email|unique:users,email');
            $user->username = $this->askWithValidate('Enter username', 'required|min:4|max:60|unique:users,username');
            $user->password = Hash::make($this->askWithValidate('Enter password', 'required|min:6|max:60', true));
            $user->super_user = 1;
            $user->manage_supers = 1;

            $userRepository->createOrUpdate($user);

            event('acl.activating', $user);

            if ($activateUserService->activate($user)) {
                $this->info('Super user is created.');

                event('acl.activated', [$user, true]);
            }

            return self::SUCCESS;
        } catch (Exception $exception) {
            $this->error('User could not be created.');
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    protected function askWithValidate(string $message, string $rules, bool $secret = false): string
    {
        do {
            if ($secret) {
                $input = $this->secret($message);
            } else {
                $input = $this->ask($message);
            }

            $validate = $this->validate(compact('input'), ['input' => $rules]);
            if ($validate['error']) {
                $this->error($validate['message']);
            }
        } while ($validate['error']);

        return $input;
    }

    protected function validate(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->messages()->first(),
            ];
        }

        return [
            'error' => false,
        ];
    }
}
