import React, { type PropsWithChildren, type ReactNode } from 'react'
import { Link } from '@inertiajs/react'

export default function Guest ({ children, footer }: PropsWithChildren<{ footer?: ReactNode }>): JSX.Element {
  return (
    <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
      <div>
        <Link href="/">
          <div className="text-2xl font-bold py-4">Stageboard</div>
        </Link>
      </div>

      <div className="card w-96 bg-base-100 shadow-xl p-6">
        {children}
      </div>

      {footer !== undefined && (
        <footer>
          {footer}
        </footer>
      )}
    </div>
  )
}
