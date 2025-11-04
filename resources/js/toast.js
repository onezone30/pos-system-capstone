// controller
setTimeout(() => {
    const toast = document.querySelector('.toast');
    if(toast) toast.remove()
}, 5000);

// livewire 
document.addEventListener('toast.success', (event) => {
    toastr.success(event.detail.message);
})

document.addEventListener('toast.error', (event) => {
    toastr.error(event.detail.message);
})

toastr.options.progressBar = true;