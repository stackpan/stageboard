import React, { useEffect, type FormEventHandler } from 'react'
import { Head, Link, useForm } from '@inertiajs/react'
import ApplicationLogo from '@/Components/ApplicationLogo'

export default function Register (): JSX.Element {
  const { data, setData, post, processing, errors, reset } = useForm({
    first_name: '',
    last_name: '',
    name: '',
    email: '',
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

    post(route('register'))
  }

  return (
    <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
      <Head title="Register"/>

      <div>
        <Link href="/">
          <ApplicationLogo className="w-20 h-20 fill-current text-gray-500"/>
        </Link>
      </div>

      <div className="card w-[36rem] bg-base-100 shadow-xl p-6">
        <h1 className="text-2xl text-center font-bold">Create New Account</h1>

        <form onSubmit={submit} className="flex flex-col gap-2 mt-2">
          <label className="form-control w-full">
            <div className="label">
              <span className="label-text">First Name</span>
            </div>
            <input
              type="text"
              name="first_name"
              value={data.first_name}
              className="input input-bordered w-full"
              autoComplete="off"
              onChange={(e) => {
                setData('first_name', e.target.value)
              }}
              required
              maxLength={32}
            />
            {errors.first_name !== undefined && (
              <div className="label">
                <span className="label-text-alt text-red-500">{errors.first_name}</span>
              </div>
            )}
          </label>

          <label className="form-control w-full">
            <div className="label">
              <span className="label-text">Last Name</span>
            </div>
            <input
              type="text"
              name="last_name"
              value={data.last_name}
              className="input input-bordered w-full"
              autoComplete="off"
              onChange={(e) => {
                setData('last_name', e.target.value)
              }}
              maxLength={64}
            />
            {errors.last_name !== undefined && (
              <div className="label">
                <span className="label-text-alt text-red-500">{errors.last_name}</span>
              </div>
            )}
          </label>

          <label className="form-control w-full">
            <div className="label">
              <span className="label-text">Username</span>
            </div>
            <input
              type="text"
              name="name"
              value={data.name}
              className="input input-bordered w-full"
              autoComplete="off"
              onChange={(e) => {
                setData('name', e.target.value)
              }}
              required
              maxLength={24}
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
              type="email"
              name="email"
              value={data.email}
              className="input input-bordered w-full"
              autoComplete="off"
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

          <div className="flex gap-4">
            <label className="form-control flex-1">
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
          </div>

          <div className="self-end mt-4">
            <Link
              href={route('login')}
              className="btn btn-active btn-link"
            >
              Already registered?
            </Link>

            <button type="submit" className="btn btn-neutral" disabled={processing}>Register</button>
          </div>
        </form>
      </div>
    </div>
  )
}
