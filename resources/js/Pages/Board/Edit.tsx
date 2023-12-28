import React, { type JSX, useState } from 'react'
import { type Board, type Collaborator, type PageProps } from '@/types'
import MainLayout from '@/Layouts/MainLayout'
import { Head, Link } from '@inertiajs/react'
import GeneralSettingsForm from '@/Pages/Board/Partials/GeneralSettingsForm'
import CollaborationSettings from '@/Pages/Board/Partials/CollaborationSettings'
import { ArrowLeftIcon } from '@heroicons/react/24/outline'

export type EditBoardProps = PageProps<{
  board: Board
  collaborators: Collaborator[]
}>

export default function Edit ({ auth, board }: EditBoardProps): JSX.Element {
  const [activeSection, setActiveSection] = useState(0)

  return (
    <MainLayout user={auth.user} headerTitle={`${board.name} Settings`}>
      <Head title={`${board.name} - Edit`} />

      <div className="flex">
        <div>
          <div className="mx-3 my-4">
            <Link
              href={route('web.page.board.show', board.aliasId)}
              as="button"
              className="btn btn-ghost btn-sm text-gray-600"
            >
              <ArrowLeftIcon className="w-4" />
              <span>Back</span>
            </Link>
          </div>
          <ul className="menu w-96 rounded-box">
            <li>
              <button
                className={activeSection === 0 ? 'font-bold bg-base-200' : ''}
                onClick={() => {
                  setActiveSection(0)
                }}>General
              </button>
            </li>
            <li>
              <button
                className={activeSection === 1 ? 'font-bold bg-base-200' : ''}
                onClick={() => {
                  setActiveSection(1)
                }}>Collaboration
              </button>
            </li>
            <li></li>
            <li>
              <button
                onClick={() => {
                  setActiveSection(2)
                }}
                className={'text-red-500' + (activeSection === 2 ? ' font-bold bg-base-200' : '')}
              >Delete
              </button>
            </li>
          </ul>
        </div>
        <div className="flex-1 p-4">
          {activeSection === 0 && (
            <GeneralSettingsForm className="max-w-2xl"/>
          )}
          {activeSection === 1 && (
            <CollaborationSettings className="max-w-2xl"/>
          )}
        </div>
      </div>
    </MainLayout>
  )
}
