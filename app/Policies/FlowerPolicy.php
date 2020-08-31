<?php

namespace App\Policies;

use App\Flower;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlowerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Flower  $flower
     * @return mixed
     */
    public function view(User $user, Flower $flower)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return in_array($user->email,[
            'olariudumitrualexandru@gmail.com',
            'emailvonbrawn@email.com',
            'alex.o@duk-tech.com',
        ]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Flower  $flower
     * @return mixed
     */
    public function update(User $user, Flower $flower)
    {
        return in_array($user->email,[
            'olariudumitrualexandru@gmail.com'
        ]);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Flower  $flower
     * @return mixed
     */
    public function delete(User $user, Flower $flower)
    {
        return in_array($user->email,[
            'olariudumitrualexandru@gmail.com'
        ]);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Flower  $flower
     * @return mixed
     */
    public function restore(User $user, Flower $flower)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Flower  $flower
     * @return mixed
     */
    public function forceDelete(User $user, Flower $flower)
    {
        //
    }
}
