import React, { useEffect, type FormEventHandler } from 'react'
import GuestLayout from '@/Layouts/GuestLayout'
import { Head, useForm } from '@inertiajs/react'

export default function ResetPassword ({ token, email }: { token: string, email: string }): JSX.Element {
  const { data, setData, post, processing, errors, reset } = useForm({
    token,
    email,
    password: '',
    password_confirmation: ''
  })

  useEffect(() => {
    return () => {
      reset('password', 'password_confirmation')
    }
  }, [])

  const submit: FormEventHandler = (e) => {
    e.preventDefault()

    post(route('password.store'))
  }

  return (
    <GuestLayout>
      <Head title="Reset Password"/>

      <form onSubmit={submit} className="flex flex-col gap-4">
        <label className="form-control w-full">
          <div className="label">
            <span className="label-text">Email</span>
          </div>
          <input
            type="text"
            name="email"
            value={data.email}
            className="input input-bordered w-full"
            autoComplete="username"
            onChange={(e) => {
              setData('email', e.target.value)
            }}
            required
            maxLength={255}
          />
          {errors.email !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.email}</span>
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

        <label className="form-control flex-1">
          <div className="label">
            <span className="label-text">Password Confirmation</span>
          </div>
          <input
            type="password"
            name="password"
            value={data.password_confirmation}
            className="input input-bordered w-full"
            autoComplete="off"
            onChange={(e) => {
              setData('password_confirmation', e.target.value)
            }}
            required
            maxLength={100}
          />
          {errors.password_confirmation !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.password_confirmation}</span>
            </div>
          )}
        </label>

        <div className="self-end mt-4">
          <button type="submit" className="btn btn-neutral" disabled={processing}>
            Reset Password
          </button>
        </div>
      </form>
    </GuestLayout>
  )
}
