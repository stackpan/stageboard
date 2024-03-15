import GuestableMainLayout from '@/Layouts/GuestableMainLayout'
import { type PageProps } from '@/types'
import { Head } from '@inertiajs/react'
import React, { type JSX } from 'react'

export default function NotFound ({ auth, status }: PageProps<{ status: number }>): JSX.Element {
  const title = {
    403: 'Forbidden',
    404: 'Not Found',
    500: 'Server Error',
    503: 'Service Unavailable'
  }[status]

  return (
    <GuestableMainLayout user={auth.user} className="justify-center">
      <Head title="Not Found"/>
      <div className="self-center text-xl font-bold">{title}</div>
    </GuestableMainLayout>
  )
}
