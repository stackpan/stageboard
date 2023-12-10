export const showModal = (id: string): void => {
  const modal: any = document.getElementById(id)
  modal.showModal()
}

export const closeModal = (id: string): void => {
  const modal: any = document.getElementById(id)
  modal.close()
}
