import GuestLayout from '@/Layouts/GuestLayout'
import { Head, Link, useForm } from '@inertiajs/react'
import React, { type FormEventHandler } from 'react'

// eslint-disable-next-line @typescript-eslint/explicit-function-return-type
export default function VerifyEmail ({ status }: { status?: string }) {
  const { post, processing } = useForm({})

  const submit: FormEventHandler = (e) => {
    e.preventDefault()

    post(route('verification.send'))
  }

  return (
    <GuestLayout
      footer={(
        <Link
          href={route('logout')}
          method="post"
          as="button"
          className="btn btn-link mt-8"
        >
          Log Out
        </Link>
      )}
    >
      <Head title="Email Verification"/>

      <div className="mb-4 text-sm text-gray-600">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the
        link we just emailed to you? If you didn&apos;t receive the email, we will gladly send you another.
      </div>

      {status === 'verification-link-sent' && (
        <div className="mb-4 font-medium text-sm text-green-600">
          A new verification link has been sent to the email address you provided during registration.
        </div>
      )}

      <form onSubmit={submit}>
        <div className="mt-4">
          <button type="submit" className="btn btn-neutral w-full" disabled={processing}>Resend Verification Email</button>
        </div>
      </form>
    </GuestLayout>
  )
}
