import React, { useEffect, type FormEventHandler } from 'react'
import GuestLayout from '@/Layouts/GuestLayout'
import { Head, useForm } from '@inertiajs/react'

export default function ConfirmPassword (): JSX.Element {
  const { data, setData, post, processing, errors, reset } = useForm({
    password: ''
  })

  useEffect(() => {
    return () => {
      reset('password')
    }
  }, [])

  const submit: FormEventHandler = (e) => {
    e.preventDefault()

    post(route('password.confirm'))
  }

  return (
    <GuestLayout>
      <Head title="Confirm Password"/>

      <div className="mb-4 text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password before continuing.
      </div>

      <form onSubmit={submit} className="flex flex-col gap-4">
        <label className="form-control w-full">
          <div className="label">
            <span className="label-text">Password</span>
          </div>
          <input
            type="password"
            name="password"
            value={data.password}
            className="input input-bordered w-full"
            autoComplete="current-password"
            onChange={(e) => {
              setData('password', e.target.value)
            }}
            required
            maxLength={100}
          />
          {errors.password !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.password}</span>
            </div>
          )}
        </label>

        <div className="self-end">
          <button type="submit" className="btn btn-neutral" disabled={processing}>
            Confirm
          </button>
        </div>
      </form>
    </GuestLayout>
  )
}
