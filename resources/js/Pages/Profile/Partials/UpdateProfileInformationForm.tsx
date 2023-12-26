import { Link, useForm, usePage } from '@inertiajs/react'
import { Transition } from '@headlessui/react'
import React, { type FormEventHandler } from 'react'
import { type PageProps } from '@/types'

export default function UpdateProfileInformation ({ mustVerifyEmail, status, className = '' }: {
  mustVerifyEmail: boolean
  status?: string
  className?: string
}): JSX.Element {
  const user = usePage<PageProps>().props.auth.user

  const { data, setData, patch, errors, processing, recentlySuccessful } = useForm({
    firstName: user.firstName,
    lastName: user.lastName,
    name: user.name,
    email: user.email
  })

  const submit: FormEventHandler = (e) => {
    e.preventDefault()

    patch(route('profile.update'))
  }

  return (
    <section className={className}>
      <header>
        <h2 className="text-xl font-medium text-gray-900">Profile Information</h2>

        <p className="mt-1 text-sm text-gray-600">
          Update your account&apos;s profile information and email address.
        </p>
      </header>

      <form onSubmit={submit} className="mt-6 space-y-6">
        <div className="flex gap-4">
          <label className="form-control w-full">
            <div className="label">
              <span className="label-text">First Name</span>
            </div>
            <input
              type="text"
              name="firstName"
              value={data.firstName}
              className="input input-bordered w-full"
              autoComplete="none"
              onChange={(e) => {
                setData('firstName', e.target.value)
              }}
              required
            />
            {errors.firstName !== undefined && (
              <div className="label">
                <span className="label-text-alt text-red-500">{errors.firstName}</span>
              </div>
            )}
          </label>

          <label className="form-control w-full">
            <div className="label">
              <span className="label-text">LastName</span>
            </div>
            <input
              type="text"
              name="lastName"
              value={data.lastName}
              className="input input-bordered w-full"
              autoComplete="none"
              onChange={(e) => {
                setData('lastName', e.target.value)
              }}
              required
            />
            {errors.lastName !== undefined && (
              <div className="label">
                <span className="label-text-alt text-red-500">{errors.lastName}</span>
              </div>
            )}
          </label>
        </div>

        <label className="form-control w-full">
          <div className="label">
            <span className="label-text">Username</span>
          </div>
          <input
            type="text"
            name="name"
            value={data.name}
            className="input input-bordered w-full"
            autoComplete="none"
            onChange={(e) => {
              setData('name', e.target.value)
            }}
            required
          />
          {errors.name !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.name}</span>
            </div>
          )}
        </label>

        <label className="form-control w-full">
          <div className="label">
            <span className="label-text">Email</span>
          </div>
          <input
            type="text"
            name="email"
            value={data.email}
            className="input input-bordered w-full"
            autoComplete="none"
            onChange={(e) => {
              setData('email', e.target.value)
            }}
            required
          />
          {errors.email !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.email}</span>
            </div>
          )}
        </label>

        {mustVerifyEmail && user.email_verified_at === undefined && (
          <div>
            <p className="text-sm mt-2 text-gray-800">
              Your email address is unverified.
              <Link
                href={route('verification.send')}
                method="post"
                as="button"
                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Click here to re-send the verification email.
              </Link>
            </p>

            {status === 'verification-link-sent' && (
              <div className="mt-2 font-medium text-sm text-green-600">
                A new verification link has been sent to your email address.
              </div>
            )}
          </div>
        )}

        <div className="flex items-center gap-4">
          <button type="submit" className="btn btn-neutral btn-sm" disabled={processing}>Save</button>

          <Transition
            show={recentlySuccessful}
            enter="transition ease-in-out"
            enterFrom="opacity-0"
            leave="transition ease-in-out"
            leaveTo="opacity-0"
          >
            <p className="text-sm text-gray-600">Saved.</p>
          </Transition>
        </div>
      </form>
    </section>
  )
}
