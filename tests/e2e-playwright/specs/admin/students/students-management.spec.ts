/**
 * External dependencies
 */
import { test, expect } from '@playwright/test';

/**
 * Internal dependencies
 */
import StudentsPage from '@e2e/pages/admin/students/students';
import { getContextByRole } from '@e2e/helpers/context';
import { createStudent, createCourse } from '@e2e/helpers/api';

test.describe.serial( 'Students Management', () => {
	const COURSE_NAME = `Course #${ Math.ceil( Math.random() * 100 ) }`;
	const STUDENT_NAME = `student${ Math.ceil( Math.random() * 100 ) }`;
	let student, course;

	// it is ensuring the browser is using a admin session.
	test.use( { storageState: getContextByRole( 'admin' ) } );

	test.beforeAll( async ( { request } ) => {
		student = await createStudent( request, STUDENT_NAME );
		course = await createCourse( request, {
			title: COURSE_NAME,
			lessons: [],
		} );
	} );

	test( 'it should add a student to a course', async ( { page } ) => {
		const studentsPage = new StudentsPage( page );
		await studentsPage.goTo();

		await studentsPage.openStudentAction(
			student.username,
			'Add to Course'
		);

		await studentsPage.modal.selectCourse( course.title.raw );
		await studentsPage.modal.addToCourseButton.click();

		await expect(
			await studentsPage.getRowByStudent( student.username )
		).toContainText( COURSE_NAME );
	} );

	test( 'it should remove the student from course', async ( { page } ) => {
		const studentsPage = new StudentsPage( page );
		await studentsPage.goTo();

		await studentsPage.openStudentAction(
			student.username,
			'Remove From Course'
		);

		await studentsPage.modal.selectCourse( course.title.raw );
		await studentsPage.modal.removeFromCourseButton.click();

		await expect(
			await studentsPage.getRowByStudent( student.username )
		).not.toContainText( COURSE_NAME );
	} );
} );