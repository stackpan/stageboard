import React, { type ReactNode, useState } from 'react'
import DeleteUserForm from './Partials/DeleteUserForm'
import UpdatePasswordForm from './Partials/UpdatePasswordForm'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm'
import { Head } from '@inertiajs/react'
import { type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'

interface SectionData {
  id: number
  menuItemButton?: {
    value: string
    className: string
  }
  Content?: () => ReactNode
}

export default function Edit ({ auth, mustVerifyEmail, status }: PageProps<{
  mustVerifyEmail: boolean
  status?: string
}>): JSX.Element {
  const [activeSection, setActiveSection] = useState(0)

  const MenuItem = ({ id, button }: {
    id: number
    button?: {
      value: string
      className: string
    }
  }): JSX.Element => {
    const disabled = activeSection === id

    return (
      <li className={disabled ? 'bg-base-200 font-bold' : ''}>
        {button !== undefined && (
          <button
            className={button.className}
            onClick={() => {
              setActiveSection(id)
            }}
            disabled={disabled}
          >{button.value}</button>
        )}
      </li>
    )
  }

  const sections: SectionData[] = [
    {
      id: 0,
      menuItemButton: {
        value: 'Profile Information',
        className: ''
      },
      Content: () => <UpdateProfileInformationForm
        mustVerifyEmail={mustVerifyEmail}
        status={status}
        className="max-w-2xl"
      />
    },
    {
      id: 1,
      menuItemButton: {
        value: 'Password',
        className: ''
      },
      Content: () => <UpdatePasswordForm className="max-w-2xl"/>
    },
    {
      id: 2
    },
    {
      id: 3,
      menuItemButton: {
        value: 'Delete Account',
        className: 'text-red-500'
      },
      Content: () => <DeleteUserForm className="max-w-2xl"/>
    }
  ]

  return (
    <MainLayout
      user={auth.user}
      headerTitle="Profile"
    >
      <Head title="Profile"/>

      <div className="flex">
        <div>
          <ul className="menu w-96 rounded-box">
            {sections.map((section) => (
              <MenuItem key={section.id} id={section.id} button={section.menuItemButton} />
            ))}
          </ul>
        </div>
        <div className="flex-1 p-4">
          {sections.map((section) => (
            ((section.id === activeSection) && (section.Content !== undefined)) && (
              <section.Content key={section.id} />
            )
          ))}
        </div>
      </div>

    </MainLayout>
  )
}
