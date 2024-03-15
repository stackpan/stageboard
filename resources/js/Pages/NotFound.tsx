import GuestableMainLayout from '@/Layouts/GuestableMainLayout'
import { Head } from '@inertiajs/react'
import React, { type JSX } from 'react'

export default function NotFound (): JSX.Element {
  return (
    <GuestableMainLayout className="justify-center">
      <Head title="Not Found"/>
      <div className="self-center text-xl font-bold">Not Found</div>
    </GuestableMainLayout>
  )
}
