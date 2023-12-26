import React, { type PropsWithChildren, type ReactNode } from 'react'
import ApplicationLogo from '@/Components/ApplicationLogo'
import { Link } from '@inertiajs/react'

export default function Guest ({ children, footer }: PropsWithChildren<{ footer?: ReactNode }>): JSX.Element {
  return (
    <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
      <div>
        <Link href="/">
          <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" />
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
