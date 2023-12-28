import React, { useRef, useState, type FormEventHandler } from 'react'
import { useForm } from '@inertiajs/react'

export default function DeleteUserForm ({ className = '' }: { className?: string }): JSX.Element {
  const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false)
  const passwordInput = useRef<HTMLInputElement>()

  const {
    data,
    setData,
    delete: destroy,
    processing,
    reset,
    errors
  } = useForm({
    password: ''
  })

  const confirmUserDeletion = (): void => {
    setConfirmingUserDeletion(true)
  }

  const deleteUser: FormEventHandler = (e) => {
    e.preventDefault()

    destroy(route('profile.destroy'), {
      preserveScroll: true,
      onSuccess: () => { closeModal() },
      onError: () => passwordInput.current?.focus(),
      onFinish: () => { reset() }
    })
  }

  const closeModal = (): void => {
    setConfirmingUserDeletion(false)

    reset()
  }

  return (
    <section className={`space-y-6 ${className}`}>
      <header>
        <h2 className="text-lg font-medium text-gray-900">Delete Account</h2>

        <p className="mt-1 text-sm text-gray-600">
          Once your account is deleted, all of its resources and data will be permanently deleted. Before
          deleting your account, please download any data or information that you wish to retain.
        </p>
      </header>

      <button className="btn btn-error btn-outline btn-sm" onClick={confirmUserDeletion}>Delete Account</button>

      <dialog className={'modal !mt-0' + (confirmingUserDeletion && ' modal-open')}>
        <div className="modal-box">
          <h3 className="font-bold text-lg">Are you sure you want to delete your account?</h3>
          <p className="py-4">
            Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
          </p>
          <form onSubmit={deleteUser} className="flex flex-col gap-4">
            <label className="form-control w-full">
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
              />
              {errors.password !== undefined && (
                <div className="label">
                  <span className="label-text-alt text-red-500">{errors.password}</span>
                </div>
              )}
            </label>

            <div className="self-end space-x-4">
              <button className="btn btn-neutral btn-outline btn-sm" onClick={closeModal}>Cancel</button>
              <button className="btn btn-error btn-sm" disabled={processing}>Delete Account</button>
            </div>
          </form>
        </div>
      </dialog>
    </section>
  )
}
