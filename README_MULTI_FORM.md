# Multi-Form Cupping System

## Overview
The user dashboard now includes 4 separate cupping forms that can be navigated between using next/previous arrow buttons. This allows users to fill out multiple cupping evaluations in a single session.

## Features

### Navigation
- **Previous Arrow (←)**: Navigate to the previous form
- **Next Arrow (→)**: Navigate to the next form
- **Form Counter**: Shows current form number (1-4)
- **Smooth Transitions**: Forms fade in/out with smooth animations

### Form Structure
Each form includes:
- **Form 1**: Complete cupping form with all attributes and categories
- **Form 2**: Complete cupping form with all attributes and categories
- **Form 3**: Basic cupping form with essential attributes
- **Form 4**: Basic cupping form with essential attributes

### Form Fields
- **Header Information**: Name, Date, Table Number, Batch Selection
- **Intensity Scales**: 1-5 rating scales for various attributes
- **Attribute Selection**: Checkboxes for all coffee characteristics (fragrance, flavor, body, acidity, sweetness)
- **Notes Section**: Text area for additional comments
- **Submit Button**: Individual save button for each form

## How to Use

1. **Start with Form 1**: The first form is displayed by default
2. **Navigate Between Forms**: Use the arrow buttons to move between forms
3. **Fill Out Each Form**: Complete the required fields for each cupping evaluation
4. **Save Individually**: Each form can be saved independently
5. **Form Reset**: After successful submission, each form resets automatically

## Technical Details

### CSS Classes
- `.form-navigation`: Navigation container with arrows and counter
- `.nav-arrow`: Styled arrow buttons
- `.form-page`: Individual form containers
- `.form-page.active`: Currently visible form
- `.form-counter`: Form number display

### JavaScript Functions
- `setupFormNavigation()`: Handles navigation between forms
- `showForm(formNumber)`: Shows specific form and hides others
- `updateNavigationState()`: Updates button states and counter
- Form submission handlers for each individual form

### Form IDs
- Forms are identified as: `form1`, `form2`, `form3`, `form4`
- Each form has unique input IDs (e.g., `fragranceIntensity1`, `fragranceIntensity2`)
- Submit buttons: `submitBtn1`, `submitBtn2`, `submitBtn3`, `submitBtn4`

## Responsive Design
- Navigation adapts to mobile devices
- Arrow buttons resize appropriately
- Form counter adjusts for smaller screens

## Browser Compatibility
- Modern browsers with ES6 support
- CSS animations and transitions
- Fetch API for form submissions

## Notes
- Each form maintains its own state independently
- Navigation is disabled at boundaries (first/last form)
- Forms automatically scroll to top when navigating
- All forms use the same processing endpoint (`process_cupping.php`)


