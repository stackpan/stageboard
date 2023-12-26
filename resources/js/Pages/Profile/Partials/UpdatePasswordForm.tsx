import React, { useRef, type FormEventHandler } from 'react'
import PrimaryButton from '@/Components/PrimaryButton'
import { useForm } from '@inertiajs/react'
import { Transition } from '@headlessui/react'

export default function UpdatePasswordForm ({ className = '' }: { className?: string }): JSX.Element {
  const passwordInput = useRef<HTMLInputElement>()
  const currentPasswordInput = useRef<HTMLInputElement>()

  const { data, setData, errors, put, reset, processing, recentlySuccessful } = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
  })

  const updatePassword: FormEventHandler = (e) => {
    e.preventDefault()

    put(route('password.update'), {
      preserveScroll: true,
      onSuccess: () => {
        reset()
      },
      onError: (errors) => {
        if (errors.password !== '') {
          reset('password', 'password_confirmation')
          passwordInput.current?.focus()
        }

        if (errors.current_password !== '') {
          reset('current_password')
          currentPasswordInput.current?.focus()
        }
      }
    })
  }

  return (
    <section className={className}>
      <header>
        <h2 className="text-lg font-medium text-gray-900">Update Password</h2>

        <p className="mt-1 text-sm text-gray-600">
          Ensure your account is using a long, random password to stay secure.
        </p>
      </header>

      <form onSubmit={updatePassword} className="mt-6 space-y-6">
        <label className="form-control w-full">
          <div className="label">
            <span className="label-text">Current Password</span>
          </div>
          <input
            type="password"
            name="current_password"
            value={data.current_password}
            className="input input-bordered w-full"
            autoComplete="off"
            onChange={(e) => {
              setData('current_password', e.target.value)
            }}
          />
          {errors.current_password !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.current_password}</span>
            </div>
          )}
        </label>

        <label className="form-control w-full">
          <div className="label">
            <span className="label-text">Password</span>
          </div>
          <input
            type="password"
            name="password"
            value={data.password}
            className="input input-bordered w-full"
            autoComplete="off"
            onChange={(e) => {
              setData('password', e.target.value)
            }}
            required
          />
          {errors.password !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.password}</span>
            </div>
          )}
        </label>

        <label className="form-control w-full">
          <div className="label">
            <span className="label-text">Confirm Password</span>
          </div>
          <input
            type="password"
            name="password_confirmation"
            value={data.password_confirmation}
            className="input input-bordered w-full"
            autoComplete="off"
            onChange={(e) => {
              setData('password_confirmation', e.target.value)
            }}
            required
          />
          {errors.password_confirmation !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.password_confirmation}</span>
            </div>
          )}
        </label>

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
