import React, { useEffect, type FormEventHandler } from 'react'
import GuestLayout from '@/Layouts/GuestLayout'
import { Head, Link, useForm } from '@inertiajs/react'

export default function Login ({ status, canResetPassword }: { status?: string, canResetPassword: boolean }): JSX.Element {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
    password: '',
    remember: false
  })

  useEffect(() => {
    return () => {
      reset('password')
    }
  }, [])

  const submit: FormEventHandler = (e) => {
    e.preventDefault()

    post(route('login'))
  }

  return (
    <GuestLayout>
      <Head title="Log in"/>

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
            autoComplete="username"
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
          />
          {errors.password !== undefined && (
            <div className="label">
              <span className="label-text-alt text-red-500">{errors.password}</span>
            </div>
          )}
        </label>

        <div className="form-control self-start">
          <label className="label cursor-pointer space-x-4">
            <input
              type="checkbox"
              name="remember"
              checked={data.remember}
              className="checkbox"
              onChange={(e) => {
                setData('remember', e.target.checked)
              }}
            />
            <span className="label-text">Remember me</span>
          </label>
        </div>

        <div className="self-end mt-4">
          {canResetPassword && (
            <Link
              href={route('password.request')}
              className="btn btn-active btn-link"
            >
              Forgot your password?
            </Link>
          )}
          <button type="submit" className="btn btn-neutral" disabled={processing}>Login</button>
        </div>
      </form>
    </GuestLayout>
  )
}
