import GuestLayout from '@/Layouts/GuestLayout'
import { Head, useForm } from '@inertiajs/react'
import React, { type FormEventHandler } from 'react'

export default function ForgotPassword ({ status }: { status?: string }): JSX.Element {
  const { data, setData, post, processing, errors } = useForm({
    email: ''
  })

  const submit: FormEventHandler = (e) => {
    e.preventDefault()

    post(route('password.email'))
  }

  return (
    <GuestLayout>
      <Head title="Forgot Password"/>

      <div className="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email address and we will email you a password
        reset link that will allow you to choose a new one.
      </div>

      {(status != null) && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

      <form onSubmit={submit} className="flex flex-col gap-2">
        <label className="form-control w-full">
          <div className="label">
            <span className="label-text">Email</span>
          </div>
          <input
            type="text"
            name="email"
            value={data.email}
            className="input input-bordered w-full"
            autoComplete="off"
            onChange={(e) => {
              setData('email', e.target.value)
            }}
          />
          {errors.email !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.email}</span>
            </div>
          )}
        </label>

        <button type="submit" className="btn btn-neutral w-full mt-4" disabled={processing}>Email Password Reset Link</button>
      </form>
    </GuestLayout>
  )
}
